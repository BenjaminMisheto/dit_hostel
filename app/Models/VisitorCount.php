<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorCount extends Model
{
    protected $fillable = [
        'total_visitors',
        'new_visitors',
        'visitors_january',
        'visitors_february',
        'visitors_march',
        'visitors_april',
        'visitors_may',
        'visitors_june',
        'visitors_july',
        'visitors_august',
        'visitors_september',
        'visitors_october',
        'visitors_november',
        'visitors_december',
    ];

    // Increment total visitors
    public function incrementTotalVisitors()
    {
        $this->increment('total_visitors');
    }

    // Increment new visitors
    public function incrementNewVisitors()
    {
        $this->increment('new_visitors');
    }

    // Increment visitors for a specific month
    public function incrementMonthlyVisitors($month)
    {
        $column = "visitors_" . strtolower($month);
        if (in_array($column, $this->fillable)) {
            $this->increment($column);
        }
    }

    // Get monthly visitors data
    public function getMonthlyVisitorsData()
    {
        return [
            'january' => $this->visitors_january,
            'february' => $this->visitors_february,
            'march' => $this->visitors_march,
            'april' => $this->visitors_april,
            'may' => $this->visitors_may,
            'june' => $this->visitors_june,
            'july' => $this->visitors_july,
            'august' => $this->visitors_august,
            'september' => $this->visitors_september,
            'october' => $this->visitors_october,
            'november' => $this->visitors_november,
            'december' => $this->visitors_december,
        ];
    }
}
