<?php

namespace SagePHP\Test;

use SagePHP\VCS\Git;
use SagePHP\System\Exec;

class GitTest extends \PHPUnit_Framework_TestCase
{

    private function getExecMock()
    {
        $exec = $this->getMock('SagePHP\System\Exec', array('run'));
        $exec->expects($this->once())
            ->method('run');
        $exec->setProcessExecutor($this->getMock('Symfony\Component\Process\Process', array(), array('')));
        return $exec;
    }

    public function testCloneRepositoryDefaultBranchDefaultFolder()
    {
        $exec = $this->getExecMock();
        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git');
        print((string) $exec->getCommand());

        $expectedCli = 'git clone git@github.com:francodacosta/phmagick.git';
        $this->assertEquals($expectedCli, (string) $exec->getCommand());
    }

    public function testCloneRepositoryDefaultBranchCustomFolder()
    {

        $exec = $this->getExecMock();
        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git', $folder = 'foo');

        $expectedCli = 'git clone git@github.com:francodacosta/phmagick.git foo';
        $this->assertEquals($expectedCli, (string) $exec->getCommand());
    }

    public function testCloneRepositoryCustomBranchDefaultFolder()
    {
        $exec = $this->getExecMock();
        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git', $folder = null, $branch = 'dev');

        $expectedCli = 'git clone git@github.com:francodacosta/phmagick.git --branch dev';
        $this->assertEquals($expectedCli, (string) $exec->getCommand());
    }

    public function testCloneRepositoryCustomBranchCustomFolder()
    {

        $exec = $this->getExecMock();
        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git', $folder = 'foo', $branch = 'dev');

        $expectedCli = 'git clone git@github.com:francodacosta/phmagick.git foo --branch dev';
        $this->assertEquals($expectedCli, (string) $exec->getCommand());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage git command not found, make sure it is installed an in the system path
     */
    public function testGitExecuteCommandWhenGitIsnNotFound()
    {
        $exec = $this->getMock('SagePHP\System\Exec', array('run', 'hasErrors'));
        $exec->expects($this->once())
            ->method('run')
            ->will($this->returnValue(127));
        $exec->expects($this->any())
            ->method('hasErrors')
            ->will($this->returnValue(true));
         $exec->setProcessExecutor($this->getMock('Symfony\Component\Process\Process', array(), array('')));

        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git', $folder = 'foo', $branch = 'dev');

    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionCode 300
     */
    public function testGitExecuteCommandWhenGitReturnsError()
    {
        $exec = $this->getMock('SagePHP\System\Exec', array('run', 'hasErrors'));
        $exec->expects($this->once())
            ->method('run')
            ->will($this->returnValue(300));
        $exec->expects($this->any())
            ->method('hasErrors')
            ->will($this->returnValue(true));
         $exec->setProcessExecutor($this->getMock('Symfony\Component\Process\Process',array(), array('')));

        $git = new Git($exec);

        $git->cloneRepository('git@github.com:francodacosta/phmagick.git', $folder = 'foo', $branch = 'dev');

    }
}
