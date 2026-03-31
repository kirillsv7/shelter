<?php

namespace Source\Domain\Animal\Enums;

enum AnimalStatus: string
{
    case Available = 'available';
    case Adopted = 'adopted';
    case Fostered = 'fostered';
    case Hospitalized = 'hospitalized';
    case OnHold = 'on_hold';
    case Quarantine = 'quarantine';
    case Deceased = 'deceased';
    case Lost = 'lost';
    case Transferred = 'transferred';
}
