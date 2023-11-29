<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;

#[Icon("🛠️ ")]
#[Name("init")]
#[Description("Initialize and configure a new <info>cosmic</info> application")]
#[Signature("init [name]")]
#[Option("name", "The name of the new application", ENV_LOCAL)]
#[Alias("app:init")]
final class InitCommand extends CosmicCommand {}
