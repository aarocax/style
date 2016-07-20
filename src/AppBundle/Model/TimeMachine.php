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
    
    if ($now['minutes']%15 === 0) {
      $minutes = $now['minutes'];          
    } else {
      if ($now['minutes'] > 0 && $now['minutes'] < 15) {
        $minutes = 15;
      }
      if ($now['minutes'] > 15 && $now['minutes'] < 30) {
        $minutes = 30;
      }
      if ($now['minutes'] > 30 && $now['minutes'] < 45) {
        $minutes = 45;
      }
      if ($now['minutes'] > 45) {
        $now['hours'] = $now['hours'] + 1;
        $minutes = 0;
      }
    }
    return date('H:i',strtotime($now['hours'].":".$minutes));
  }

    public static function arrayIntervaloHoras($inicial, $final, $interval){
        $horaInicial=$inicial;
        $horaFinal=$final;
        $minutoAnadir=$interval;
        while ( $horaInicial != $horaFinal) {
            $horas[] = $horaInicial; 
            $segundos_horaInicial=strtotime($horaInicial);
            $segundos_minutoAnadir=$minutoAnadir*60;
            $nuevaHora=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
            $horaInicial=$nuevaHora;
        }
        return $horas;
    }

    public static function arrayHoras($interval){
        $horaInicial="08:00";
        $minutoAnadir=$interval;
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