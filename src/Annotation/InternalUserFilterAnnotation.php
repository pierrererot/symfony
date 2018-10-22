<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 30/07/2018
 * Time: 13:57
 */

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class InternalUserFilterAnnotation
{
    public $targetFieldName;
}