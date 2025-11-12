<?php

namespace App\Enums;

enum Tooth: string
{
    // Upper Right (1)
    case Tooth11 = '11';
    case Tooth12 = '12';
    case Tooth13 = '13';
    case Tooth14 = '14';
    case Tooth15 = '15';
    case Tooth16 = '16';
    case Tooth17 = '17';
    case Tooth18 = '18';

    // Upper Left (2)
    case Tooth21 = '21';
    case Tooth22 = '22';
    case Tooth23 = '23';
    case Tooth24 = '24';
    case Tooth25 = '25';
    case Tooth26 = '26';
    case Tooth27 = '27';
    case Tooth28 = '28';

    // Lower Left (3)
    case Tooth31 = '31';
    case Tooth32 = '32';
    case Tooth33 = '33';
    case Tooth34 = '34';
    case Tooth35 = '35';
    case Tooth36 = '36';
    case Tooth37 = '37';
    case Tooth38 = '38';

    // Lower Right (4)
    case Tooth41 = '41';
    case Tooth42 = '42';
    case Tooth43 = '43';
    case Tooth44 = '44';
    case Tooth45 = '45';
    case Tooth46 = '46';
    case Tooth47 = '47';
    case Tooth48 = '48';

    // ----------------------------------------
    // Helpers
    // ----------------------------------------

    public function quadrant(): int
    {
        $num = (int) substr($this->value, 0, 1);
        return $num;
    }

    public function isUpper(): bool
    {
        return in_array($this->quadrant(), [1, 2]);
    }

    public function isLower(): bool
    {
        return in_array($this->quadrant(), [3, 4]);
    }

    public function isLeft(): bool
    {
        return in_array($this->quadrant(), [2, 3]);
    }

    public function isRight(): bool
    {
        return in_array($this->quadrant(), [1, 4]);
    }

    // ----------------------------------------
    // Notation Systems
    // ----------------------------------------

    public function fdi(): string
    {
        return $this->value;
    }

    public function universal(): string
    {
        return match ($this) {
            // Upper right → Upper left (1–16)
            Tooth::Tooth18 => '1',
            Tooth::Tooth17 => '2',
            Tooth::Tooth16 => '3',
            Tooth::Tooth15 => '4',
            Tooth::Tooth14 => '5',
            Tooth::Tooth13 => '6',
            Tooth::Tooth12 => '7',
            Tooth::Tooth11 => '8',
            Tooth::Tooth21 => '9',
            Tooth::Tooth22 => '10',
            Tooth::Tooth23 => '11',
            Tooth::Tooth24 => '12',
            Tooth::Tooth25 => '13',
            Tooth::Tooth26 => '14',
            Tooth::Tooth27 => '15',
            Tooth::Tooth28 => '16',

            // Lower left → Lower right (17–32)
            Tooth::Tooth38 => '17',
            Tooth::Tooth37 => '18',
            Tooth::Tooth36 => '19',
            Tooth::Tooth35 => '20',
            Tooth::Tooth34 => '21',
            Tooth::Tooth33 => '22',
            Tooth::Tooth32 => '23',
            Tooth::Tooth31 => '24',
            Tooth::Tooth41 => '25',
            Tooth::Tooth42 => '26',
            Tooth::Tooth43 => '27',
            Tooth::Tooth44 => '28',
            Tooth::Tooth45 => '29',
            Tooth::Tooth46 => '30',
            Tooth::Tooth47 => '31',
            Tooth::Tooth48 => '32',
        };
    }

    public function palmer(): string
    {
        return match ($this->quadrant()) {
            1 => '⏌' . substr($this->value, 1, 1), // Upper Right
            2 => '⏍' . substr($this->value, 1, 1), // Upper Left
            3 => '⏋' . substr($this->value, 1, 1), // Lower Left
            4 => '⏊' . substr($this->value, 1, 1), // Lower Right
        };
    }
}
