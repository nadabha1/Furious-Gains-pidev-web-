<?php

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringToFileTransformer implements DataTransformerInterface
{
public function transform($value)
{
// Transform the string value to a File object
if ($value) {
return new File($value);
}

return null;
}

public function reverseTransform($value)
{
// Transform the File object back to a string
if ($value instanceof File) {
return $value->getPathname();
}

return null;
}
}