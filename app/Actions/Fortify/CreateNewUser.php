<?php
namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\ElligableStudent;
use App\Models\Semester;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; // For generating OTP

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     * @return User
     */
    public function create(array $input): User
    {
        // Validate the registration number and password
        $validator = Validator::make($input, [
            'registration_number' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        // If validation fails, throw ValidationException
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check if the registration number already has an account in the 'users' table
        $existingUser = User::where('registration_number', $input['registration_number'])->first();

        if ($existingUser) {
            $validator->errors()->add('registration_number', 'Registration number already has an existing account.');
            throw new ValidationException($validator);
        }

        // Check if the registration number exists in the 'elligable_students' table
        $student = ElligableStudent::where('registration_number', $input['registration_number'])->first();

        if (!$student) {
            $validator->errors()->add('registration_number', 'Registration number is not found in the eligible students table.');
            throw new ValidationException($validator);
        }

        // Check if the registration number has an associated email in the 'elligable_students' table
        if (!$student->email) {
            $validator->errors()->add('registration_number', 'No email associated with this registration number.');
            throw new ValidationException($validator);
        }

        // Fetch the open semester
        $semester = Semester::where('is_closed', 0)->first();

        if (!$semester) {
            $validator->errors()->add('semester', 'Semester is not yet started.');
            throw new ValidationException($validator);
        }

        // Generate a random OTP (e.g., 6 digits)
        $otp = Str::random(6);

        // Send OTP to the student's email
        Mail::to($student->email)->send(new \App\Mail\OtpMail($otp));

        // Optionally store the OTP in the database or cache for later verification
        // Example: Store it in the session or database temporarily for later verification
        session(['otp' => $otp]); // Using session to store OTP for now

        // Create the new user with the name and other details from the eligible student table
        $user = User::create([
            'name' => $student->student_name, // Take the name from eligible students
            'registration_number' => $input['registration_number'],
            'email' => $student->email, // Set email from the eligible student table
            'password' => Hash::make($input['password']),
            'semester_id' => $semester->id,
            'sponsorship' => $student->sponsorship, // Take sponsorship from eligible student
            'phone' => $student->phone, // Take phone from eligible student
            'gender' => $student->gender, // Take gender from eligible student
            'nationality' => $student->nationality, // Take nationality from eligible student
            'course' => $student->course, // Take course from eligible student
        ]);

        return $user;
    }
}
