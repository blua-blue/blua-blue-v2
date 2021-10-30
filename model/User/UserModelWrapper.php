<?php
namespace Neoan3\Model\User;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class UserModelWrapper extends UserModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $customer_id = null;
	private ?string $user_type = null;
	private ?string $user_name = null;
	private ?string $image_id = null;
	private string $insert_date;
	private ?string $delete_date = null;
	private array $user_email = [];
	private array $user_password = [];
	private array $user_profile = [];
	private array $user_role = [];

	public function getId(): mixed
	{
		return $this->id;
	}

	public function setId($input): static
	{
		$this->id = $input;
		return $this;
	}

	public function getCustomerId(): mixed
	{
		return $this->customer_id;
	}

	public function setCustomerId($input): static
	{
		$this->customer_id = $input;
		return $this;
	}

	public function getUserType(): mixed
	{
		return $this->user_type;
	}

	public function setUserType($input): static
	{
		$this->user_type = $input;
		return $this;
	}

	public function getUserName(): mixed
	{
		return $this->user_name;
	}

	public function setUserName($input): static
	{
		$this->user_name = $input;
		return $this;
	}

	public function getImageId(): mixed
	{
		return $this->image_id;
	}

	public function setImageId($input): static
	{
		$this->image_id = $input;
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

	public function getUserEmail(): array
	{
		return $this->user_email;
	}

	public function addUserEmail(array $newSub): static
	{
		$this->user_email[] = $newSub;
		return $this;
	}

	public function removeUserEmail(string $id): static
	{
		foreach ($this->user_email as $i => $any){
			if($any['id'] === $id){
				$this->user_email[$i]['delete_date'] = null;
			}
		}
		return $this;
	}

	public function getUserPassword(): array
	{
		return $this->user_password;
	}

	public function addUserPassword(array $newSub): static
	{
		$this->user_password[] = $newSub;
		return $this;
	}

	public function removeUserPassword(string $id): static
	{
		foreach ($this->user_password as $i => $any){
			if($any['id'] === $id){
				$this->user_password[$i]['delete_date'] = null;
			}
		}
		return $this;
	}

	public function getUserProfile(): array
	{
		return $this->user_profile;
	}

	public function addUserProfile(array $newSub): static
	{
		$this->user_profile[] = $newSub;
		return $this;
	}

	public function removeUserProfile(string $id): static
	{
		foreach ($this->user_profile as $i => $any){
			if($any['id'] === $id){
				$this->user_profile[$i]['delete_date'] = null;
			}
		}
		return $this;
	}

	public function getUserRole(): array
	{
		return $this->user_role;
	}

	public function addUserRole(array $newSub): static
	{
		$this->user_role[] = $newSub;
		return $this;
	}

	public function removeUserRole(string $id): static
	{
		foreach ($this->user_role as $i => $any){
			if($any['id'] === $id){
				$this->user_role[$i]['delete_date'] = null;
			}
		}
		return $this;
	}

}