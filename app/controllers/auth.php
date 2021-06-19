<?php

class AuthController extends Controller
{

    function __construct() {
        mssql_connect();
    }

    /**
     * Default Action for Home Controller
     */
    function index() {
       
    }

    function getAllUsers() {
        $sql = "SELECT IDUser, Username, Fullname, Phone, Email FROM MetaUser WHERE IsGroup = 0 AND M_IsDeleted = 0";
        $data = mssql_query($sql);
        return self::ajs($data);
    }

    function getUser() {
        secure_api_method();
        
        $sql = sprintf("SELECT IDUser, Username, Fullname, Phone, Email FROM MetaUser WHERE IsGroup = 0 AND M_IsDeleted = 0 AND Username LIKE '%s'", get('username'));
        $data = mssql_query($sql);
        return self::ajs($data);
    }
}