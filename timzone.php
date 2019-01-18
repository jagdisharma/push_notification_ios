<?php 
function myTime(){
    global $timeOffset;
    return time()+$timeOffset;
}	
?>