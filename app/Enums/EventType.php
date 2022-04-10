<?php

namespace App\Enums;

enum EventType: string
{
    case LoggedIn = 'logged in';
    case AffiliateEarning = 'affiliate earning';
    case Transaction = 'transaction';
    case Withdrawal = 'withdrawal';
    case Deposit = 'deposit';
}
