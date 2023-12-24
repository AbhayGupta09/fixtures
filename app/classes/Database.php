<?php


namespace App\classes;


/**
 * Summary of Database
 */
class Database
{
    /**
     * Summary of db
     * @return \mysqli|bool
     */
    public static function db()
    {
        $link = mysqli_connect('localhost','root','','fixture_php');
        return $link;
    }
}
