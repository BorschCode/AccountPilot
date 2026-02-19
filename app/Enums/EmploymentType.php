<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Contract = 'contract';
    case Internship = 'internship';
    case Freelance = 'freelance';

    public function label(): string
    {
        return match ($this) {
            EmploymentType::FullTime => 'Full-Time',
            EmploymentType::PartTime => 'Part-Time',
            EmploymentType::Contract => 'Contract',
            EmploymentType::Internship => 'Internship',
            EmploymentType::Freelance => 'Freelance',
        };
    }
}
