<?php

namespace SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * studentInClass
 *
 * @ORM\Table(name="student_in_class")
 * @ORM\Entity(repositoryClass="SchoolBundle\Repository\studentInClassRepository")
 */
class StudentInClass
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="class_id", type="integer")
     */
    private $classId;

    /**
     * @var int
     *
     * @ORM\Column(name="student_id", type="integer")
     */
    private $studentId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set classId
     *
     * @param integer $classId
     *
     * @return studentInClass
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

    /**
     * Get classId
     *
     * @return int
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * Set studentId
     *
     * @param integer $studentId
     *
     * @return studentInClass
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId
     *
     * @return int
     */
    public function getStudentId()
    {
        return $this->studentId;
    }
}

