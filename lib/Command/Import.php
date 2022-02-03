<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2022 Côme Chilliet <come.chilliet@nextcloud.com>
 *
 * @author Côme Chilliet <come.chilliet@nextcloud.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\UserMigration\Command;

use OCA\UserMigration\Service\UserMigrationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command {

	/** @var UserMigrationService */
	private $migrationService;

	public function __construct(UserMigrationService $migrationService) {
		parent::__construct();
		$this->migrationService = $migrationService;
	}

	protected function configure(): void {
		$this
			->setName('user:import')
			->setDescription('Import a user.')
			->addArgument(
				'archive',
				InputArgument::REQUIRED,
				'path of the export archive to import'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		try {
			$this->migrationService->import($input->getArgument('archive'), $output);
		} catch (\Exception $e) {
			$output->writeln("$e");
			$output->writeln("<error>" . $e->getMessage() . "</error>");
			return $e->getCode() !== 0 ? (int)$e->getCode() : 1;
		}

		return 0;
	}
}
