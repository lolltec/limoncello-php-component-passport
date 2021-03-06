<?php declare(strict_types=1);

namespace Limoncello\Passport\Package;

/**
 * Copyright 2015-2019 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Doctrine\DBAL\Connection;
use Limoncello\Contracts\Application\ContainerConfiguratorInterface as CCI;
use Limoncello\Contracts\Container\ContainerInterface as LimoncelloContainerInterface;
use Limoncello\Passport\Adaptors\MySql\PassportServerIntegration;
use Limoncello\Passport\Adaptors\MySql\TokenRepository;
use Limoncello\Passport\Contracts\Entities\DatabaseSchemaInterface;
use Limoncello\Passport\Contracts\PassportServerIntegrationInterface;
use Limoncello\Passport\Contracts\Repositories\TokenRepositoryInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * @package Limoncello\Passport
 */
class MySqlPassportContainerConfigurator extends BasePassportContainerConfigurator implements CCI
{
    /**
     * @inheritdoc
     */
    public static function configureContainer(LimoncelloContainerInterface $container): void
    {
        static::baseConfigureContainer($container);

        $container[PassportServerIntegrationInterface::class] = function (
            PsrContainerInterface $container
        ): PassportServerIntegrationInterface {
            return new PassportServerIntegration($container);
        };

        $container[TokenRepositoryInterface::class] = function (
            PsrContainerInterface $container
        ): TokenRepositoryInterface {
            $connection = $container->get(Connection::class);
            $schema     = $container->get(DatabaseSchemaInterface::class);

            return new TokenRepository($connection, $schema);
        };
    }
}
