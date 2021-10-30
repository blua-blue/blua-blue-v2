<?php
namespace Neoan3\Model\Category;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class CategoryModelWrapper extends CategoryModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $name = null;
	private string $insert_date;
	private ?string $delete_date = null;

	public function getId(): mixed
	{
		return $this->id;
	}

	public function setId($input): static
	{
		$this->id = $input;
		return $this;
	}

	public function getName(): mixed
	{
		return $this->name;
	}

	public function setName($input): static
	{
		$this->name = $input;
		return $this;
	}

	public function getInsertDate(): mixed
	{
		return $this->insert_date;
	}

	public function setInsertDate($input): static
	{
		$this->insert_date = $input;
		return $this;
	}

	public function getDeleteDate(): mixed
	{
		return $this->delete_date;
	}

	public function setDeleteDate($input): static
	{
		$this->delete_date = $input;
		return $this;
	}

}