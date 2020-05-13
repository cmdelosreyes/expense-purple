<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = "expenses";

    protected $fillable = [
        'expense_category_id',
        'amount',
        'entry_date',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'entry_date'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function Category()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
