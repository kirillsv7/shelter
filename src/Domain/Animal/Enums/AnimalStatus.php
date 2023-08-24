<?php

namespace Source\Domain\Animal\Enums;

enum AnimalStatus: string
{
    case Checking = 'checking';
    case Adoption = 'adoption';
    case Adopted = 'adopted';
    case Deceased = 'deceased';
}
