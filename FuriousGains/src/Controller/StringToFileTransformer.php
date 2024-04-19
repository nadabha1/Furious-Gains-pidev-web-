<?php

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{public function transform($value)
{
    // Transform the File object to a string representation
    if ($value instanceof File) {
        return $value->getPathname();
    }

    return null;
}

    public function reverseTransform($value)
    {
        // Transform the string representation to a File object
        if (!$value) {
            return null;
        }

        try {
            return new File($value);
        } catch (\Exception $e) {
            throw new TransformationFailedException(sprintf('Unable to transform the string into a File object: "%s"', $e->getMessage()));
        }
    }
}