<?php

namespace App\Helpers;

class Helper
{
    public static function getMonthText($m)
    {
        switch ( $m ) {
            case 1:
                $m_name = 'январь';
                break;
            case 2:
                $m_name = 'февраль';
                break;
            case 3:
                $m_name = 'март';
                break;
            case 4:
                $m_name = 'апрель';
                break;
            case 5:
                $m_name = 'май';
                break;
            case 6:
                $m_name = 'июнь';
                break;
            case 7:
                $m_name = 'июль';
                break;
            case 8:
                $m_name = 'август';
                break;
            case 9:
                $m_name = 'сентябрь';
                break;
            case 10:
                $m_name = 'октябрь';
                break;
            case 11:
                $m_name = 'ноябрь';
                break;
            case 12:
                $m_name = 'декабрь';
                break;
            default:
                $m      = "Ошибка даты";
                $m_name = "";
                break;

        }

        return $m_name;
    }

}