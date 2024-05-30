<?php

function scriptTohtml($str){
    echo htmlentities($str, ENT_QUOTES, 'UTF-8');
}