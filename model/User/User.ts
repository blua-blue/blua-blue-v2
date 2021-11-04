interface User{
	readonly id?: string,
	customer_id?: string,
	user_type?: number,
	user_name?: string,
	image_id?: string,
	readonly insert_date_st: number,
	insert_date: string,
	readonly delete_date_st: number,
	delete_date?: string,
	user_email: Array<User_email>,
	user_password: Array<User_password>,
	user_profile: Array<User_profile>,
	user_role: Array<User_role>,
	user_store: Array<User_store>,
}
interface User_email{
	readonly id?: string,
	user_id?: string,
	email?: string,
	confirm_code?: string,
	readonly insert_date_st: number,
	insert_date: string,
	readonly confirm_date_st: number,
	confirm_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
}
interface User_password{
	readonly id?: string,
	user_id?: string,
	password: string,
	confirm_code?: string,
	readonly insert_date_st: number,
	insert_date: string,
	readonly confirm_date_st: number,
	confirm_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
}
interface User_profile{
	readonly id?: string,
	user_id?: string,
	first_name: string,
	last_name: string,
	readonly insert_date_st: number,
	insert_date: string,
	readonly delete_date_st: number,
	delete_date?: string,
}
interface User_role{
	readonly id?: string,
	user_id?: string,
	role: string,
	readonly insert_date_st: number,
	insert_date: string,
	readonly delete_date_st: number,
	delete_date?: string,
}
interface User_store{
	readonly id?: string,
	readonly insert_date_st: number,
	insert_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
	user_id?: string,
	key: string,
	value?: string,
}

export {User, User_email, User_password, User_profile, User_role, User_store}