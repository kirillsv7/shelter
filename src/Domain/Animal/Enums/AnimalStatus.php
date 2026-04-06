<?php

namespace Source\Domain\Animal\Enums;

enum AnimalStatus: string
{
    case Adopted = 'adopted';
    case Available = 'available';
    case Deceased = 'deceased';
    case Fostered = 'fostered';
    case Hospitalized = 'hospitalized';
    case Lost = 'lost';
    case OnHold = 'on_hold';
    case Quarantine = 'quarantine';
    case Reserved = 'reserved';
    case Transferred = 'transferred';
}
