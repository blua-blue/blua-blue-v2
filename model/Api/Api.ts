interface Api{
	readonly id?: string,
	readonly insert_date_st: number,
	insert_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
	user_id?: string,
	scope: string,
	name: string,
	remote?: string,
	api_key?: string,
	api_usage: Array<Api_usage>,
}
interface Api_usage{
	readonly id?: string,
	readonly insert_date_st: number,
	insert_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
	api_id?: string,
	day: number,
	request: string,
}

export {Api, Api_usage}