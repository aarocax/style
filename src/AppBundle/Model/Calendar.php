<?php


namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Room;
use AppBundle\Entity\Appointment;
use AppBundle\Model\TimeMachine;



class Calendar
{

	private $em;

	function __construct(EntityManager $entityManager)
    {
    	$this->em = $entityManager;
    }

    public function summary($salas, $date)
    {
        $summary = array();
        $diaSemana = array();
        $horario = array();
        foreach ($salas as $key => $value) {
            foreach ($salas[$key]['semana'] as $key2 => $value2) {
                $dateStr = $date->format('Y-m-d');
                if (is_array($value2)) {
                    $horario = $value2;
                    $diaSemana =  $key2;
                }
                if ($value2 == $dateStr) {
                    $sumary[$key]['id'] = $salas[$key]['id'];
                    $sumary[$key] = $horario;

                }
                
            }
        }
        return $sumary;
    }

    public function makeCalendar($interval, $date)
    {
        // leo las distintas salas
        $rooms = $this->em->getRepository('AppBundle:Room')->findAll();

        $calendar = array();

        foreach ($rooms as $key => $room) {
            // leo los schedules de la semana
            $schedules = $this->getWeekSchedules($date, $room->getId());

            // leo los schedules del día para la vista resumen
            //$schedulesPresentDay = $this->getPresentDaySchedules($date, $room->getId());
            //dump($schedulesPresentDay);
            //$calendar['summary'][] = $schedulesPresentDay;
            

            // creo un array de horas para poder imprimir en twig
            $horario = TimeMachine::arrayHoras($interval);

            // creo el array que contendrá las citas
            $firstDayOfWeek = TimeMachine::firstDayOfWeek($date);
            $semana = $this->createArrayWeek($horario,  $firstDayOfWeek);

            // llenamos el array semana con los schedules 
            $semana = $this->putSchedulesInWeekarray($semana, $schedules, $interval);
            $horario = $this->createArrayHours($interval);

            $calendar['horario'] = $horario;

            $calendar['salas'][$room->getName()]['semana'] = $semana;
            $calendar['salas'][$room->getName()]['id'] = $room->getId();

        }

        return $calendar;
    }

    private function getWeekSchedules($date, $room_id)
    {
        $firstDayOfWeek = TimeMachine::firstDayOfWeek($date);
        $lastDayOfWeek = TimeMachine::lastDayOfWeek($date);
        $schedules = $this->em->getRepository('AppBundle:Schedule')->getRoomsSchedulesWeek($firstDayOfWeek, $lastDayOfWeek, $room_id);
        return $schedules;
    }

    private function getPresentDaySchedules($date, $room_id)
    {
        $date = TimeMachine::setTimeToZero($date);
        $schedulesPresentDay = $this->em->getRepository('AppBundle:Schedule')->getSchedulesPresentDay($date, $room_id);
        return $schedulesPresentDay;
    }

    private function putSchedulesInWeekarray($semana, Array $schedules, $interval)
    {
        $diasSemana = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

        foreach ($schedules as $schedule) {

           
            $h = array('minutes' => (int)$schedule['startingHour']->format('i'),'hours'=>(int)$schedule['startingHour']->format('H'));
            $hf = array('minutes' => (int)$schedule['finishHour']->format('i'),'hours'=>(int)$schedule['finishHour']->format('H'));
            
            
            // Ojo, el redondeo de horas falla
            $hr = TimeMachine::roundMinutes($h); // obtengo la hora inicio redondeada
            $hfr = TimeMachine::roundMinutes($hf); // obtengo la hora final redondeada

            $intervaloHoras = TimeMachine::arrayIntervaloHoras($hr, $hfr, $interval);
 
            $d = $diasSemana[date('N', strtotime($schedule['scheduleDate']->format('Y-m-d')))-1];
            foreach ($intervaloHoras as $value) {
                $semana[$d][$value]['nombreCita']=$schedule['name']." ".$schedule['lastName'];
                $semana[$d][$value]['idCita']=$schedule['id'];
                $semana[$d][$value]['idAppointment']=$schedule['appointment'];
            }
            
        }
        return $semana;
    }

    private function createArrayWeek($horario, $fecha){
        //$f = new \DateTime($fecha);
        $f = clone $fecha;
        $semana = array(
            'Lunes' => $horario,
            'fechaLunes'=>$f->format('Y-m-d'),
            'Martes' => $horario,
            'fechaMartes'=>date_modify($f, '+1 day')->format('Y-m-d'),
            'Miercoles' => $horario,
            'fechaMiercoles'=>date_modify($f, '+1 day')->format('Y-m-d'),
            'Jueves' => $horario,
            'fechaJueves'=>date_modify($f, '+1 day')->format('Y-m-d'),
            'Viernes' => $horario,
            'fechaViernes'=>date_modify($f, '+1 day')->format('Y-m-d'),
            'Sabado' => $horario,
            'fechaSabado'=>date_modify($f, '+1 day')->format('Y-m-d'),
            'Domingo' => $horario,
            'fechaDomingo'=>date_modify($f, '+1 day')->format('Y-m-d'),
        );
        return $semana;
    }

    private function createArrayHours($interval = 10)
    {
        $horario = array();
        $horaInicial="08:00";
        $minutoAnadir=$interval;
        for ($i=0; $i < 56; $i++) { 
            $horario[$i] = $horaInicial; 
            $segundos_horaInicial=strtotime($horaInicial);
            $segundos_minutoAnadir=$minutoAnadir*60;
            $nuevaHora=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
            $horaInicial=$nuevaHora;
        }
        return $horario;
    }

}