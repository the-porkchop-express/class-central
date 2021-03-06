<?php
/**
 * Created by PhpStorm.
 * User: dhawal
 * Date: 4/29/16
 * Time: 11:02 PM
 */

namespace ClassCentral\SiteBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface;
use ClassCentral\SiteBundle\Entity\Course as CourseEntity;

class Course
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadImageIfNecessary( $imageUrl, CourseEntity $course)
    {
        $kuber = $this->container->get('kuber');
        $uniqueKey = basename($imageUrl);
        $uniqueKey = reset(explode('?', $uniqueKey));
        if( $kuber->hasFileChanged( Kuber::KUBER_ENTITY_COURSE,Kuber::KUBER_TYPE_COURSE_IMAGE, $course->getId(),$uniqueKey ) )
        {
            // Upload the file
            $filePath = '/tmp/course_'.$uniqueKey;
            file_put_contents($filePath,file_get_contents($imageUrl));
            $kuber->upload(
                $filePath,
                Kuber::KUBER_ENTITY_COURSE,
                Kuber::KUBER_TYPE_COURSE_IMAGE,
                $course->getId(),
                null,
                $uniqueKey
            );

        }
    }

    // Used for spotlight section
    public function getRandomPaidCourse()
    {
        $finder = $this->container->get('course_finder');
        $results = $finder->paidCourses();
        $courses = array();
        foreach($results['hits']['hits'] as $course)
        {
            $courses[] = $course['_source'];
        }

        $index = rand(0,count($courses)-1);

        return $courses[$index];
    }

    public function getCourseImage(CourseEntity $course)
    {
        return $this->getCourseImageFromId($course->getId());
    }

    public function getCourseImageFromId($courseId)
    {
        $kuber = $this->container->get('kuber');
        $url = $kuber->getUrl( Kuber::KUBER_ENTITY_COURSE ,Kuber::KUBER_TYPE_COURSE_IMAGE, $courseId );
        return $url;
    }
}