<?php

/*-------- CREACIÓN DE LA CONEXIÓN DE LA DB Y TOKENS DE SEGURIDAD ------*/

const SERVER = "localhost";
const DB = "prestamos";
const USER = "root";
const PASS = "";

const SGBD = "mysql:host=" . SERVER . ";dbname=" . DB;

const METHOD = "AES-256-CBC";
const SECRET_KEY = '$PRESTAMOS@2020';
const SECRET_IV = '037970';
