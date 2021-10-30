<?php
namespace Neoan3\Model\Api;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class ApiModelWrapper extends ApiModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $insert_date;
	private ?string $delete_date = null;
	private string $user_id;
	private string $scope;
	private string $name;
	private ?string $remote = null;
	private ?string $api_key = null;
	private array $api_usage = [];

	public function getId(): mixed
	{
		return $this->id;
	}

	public function setId($input): static
	{
		$this->id = $input;
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

	public function getUserId(): mixed
	{
		return $this->user_id;
	}

	public function setUserId($input): static
	{
		$this->user_id = $input;
		return $this;
	}

	public function getScope(): mixed
	{
		return $this->scope;
	}

	public function setScope($input): static
	{
		$this->scope = $input;
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

	public function getRemote(): mixed
	{
		return $this->remote;
	}

	public function setRemote($input): static
	{
		$this->remote = $input;
		return $this;
	}

	public function getApiKey(): mixed
	{
		return $this->api_key;
	}

	public function setApiKey($input): static
	{
		$this->api_key = $input;
		return $this;
	}

	public function getApiUsage(): array
	{
		return $this->api_usage;
	}

	public function addApiUsage(array $newSub): static
	{
		$this->api_usage[] = $newSub;
		return $this;
	}

	public function removeApiUsage(string $id): static
	{
		foreach ($this->api_usage as $i => $any){
			if($any['id'] === $id){
				$this->api_usage[$i]['delete_date'] = null;
			}
		}
		return $this;
	}

}