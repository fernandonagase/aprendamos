<?php
namespace aprendamos\view\reports;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Index extends View
{
    public function build()
    {
        $indexTEMPL = $this->loadTemplate('reports/index');

        $reports = $this->models['reports'];
        $average = $this->models['average'];
        $overAverage = $this->models['overAverage'];
        $belowAverage = $this->models['belowAverage'];
        
        $rowsTEMPL = [];
        foreach($reports as $name => $grade) {
            $reportRow = $this->loadTemplate('reports/index_row');
            $reportRow->set('name', $name);
            $reportRow->set('grade', $grade);
            $rowsTEMPL[] = $reportRow;
        }

        $indexTEMPL->set('reports', Template::merge($rowsTEMPL));
        $indexTEMPL->set('average', $average);
        $indexTEMPL->set('overAverage', $overAverage);
        $indexTEMPL->set('belowAverage', $belowAverage);

        $this->setTitle('Resumo de notas da turma - Aprendamos');
        $this->setContent($indexTEMPL->resolve());
    }
}