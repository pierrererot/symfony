<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 08/08/2018
 * Time: 15:07
 */

namespace App\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AuthenticationMethodType extends Type
{
    const ENUM_AUTHENTICATION_METHOD = 'authentication_method';

    const AUTHENTICATED_BY_GOOGLE = 'google';
    const AUTHENTICATED_BY_LDAP = 'ldap';
    const AUTHENTICATED_BY_DEFAULT = 'default';

    private static $list = [
        self::AUTHENTICATED_BY_GOOGLE,
        self::AUTHENTICATED_BY_LDAP,
        self::AUTHENTICATED_BY_DEFAULT
    ];

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::ENUM_AUTHENTICATION_METHOD;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, static::$list)) {
            throw new \InvalidArgumentException("Invalid status");
        }
        return $value;
    }

    public function getName()
    {
        return self::ENUM_AUTHENTICATION_METHOD;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}