<?php

namespace AppBundle\Model;

class TimeMachine
{
	public static function firstDayOfWeek($date)
    {
    	$d = clone $date;
		if($d->format('l') != 'Monday'){
		    $d->modify('Last Monday');
		}
        $d->setTime(00, 00, 00);
		return $d;
	}

	public static function lastDayOfWeek($date)
	{
		$d = clone $date;
		if($d->format('l') != 'Sunday'){
		    $d->modify('Next Sunday');
		}
        $d->setTime(00, 00, 00);
		return $d;
	}

    public static function setTimeToZero($date)
    {
        $d = clone $date;
        $d->setTime(00, 00, 00);
        return $d;
    }

	public static function getTimeOfDatetime($date)
	{
		return $date->format('H:i');
	}

	public static function getDayOfDatetime($date)
	{
		return $date->format('d');
	}

	/*
	 * toma un array del tipo array('minutes'=>minutos,'hours'=>hora)
	 * y retorna un array del mismo tipo con los minutos redondeados en un múltiplo de 15
    */ 
	public static function roundMinutes($now)
	{
        $now['minutes']%15;
        $minutes = $now['minutes'] - $now['minutes']%15;
       
         //redondeo en intérvalos de 15min, al alza
        $rmin  = $now['minutes']%15;
        $minutes = $now['minutes'] + $rmin;
        return date('H:i',strtotime($now['hours'].":".$minutes));
    }

    public static function arrayIntervaloHoras($inicial, $final){
        $horaInicial=$inicial;
        $horaFinal=$final;
        $minutoAnadir=15;
        while ( $horaInicial != $horaFinal) {
            $horas[] = $horaInicial; 
            $segundos_horaInicial=strtotime($horaInicial);
            $segundos_minutoAnadir=$minutoAnadir*60;
            $nuevaHora=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
            $horaInicial=$nuevaHora;
        }
        return $horas;
    }

    public static function arrayHoras(){
        $horaInicial="08:00";
        $minutoAnadir=15;
        for ($i=0; $i < 56; $i++) { 
            $horas[$horaInicial] = array('horaCita'=>$horaInicial,'nombreCita'=>null,'idCita'=>null);
            $segundos_horaInicial=strtotime($horaInicial);
            $segundos_minutoAnadir=$minutoAnadir*60;
            $nuevaHora=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
            $horaInicial=$nuevaHora;
        }
        return $horas;
    }
}