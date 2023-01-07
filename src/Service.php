<?php

namespace MainaDavid\MultiShortcodeMpesa;

abstract class Service
{
	/**
	 * It returns an array with a status of 'error' and the data passed to it
	 * 
	 * @param data The data you want to send back to the client.
	 * 
	 * @return An array with a status of error and the data passed in.
	 */
	protected static function error($data)
	{
		return [
			'status' 	=> 'error',
			'data'		=> $data
		];
	}

	/**
	 * It returns an array with a status of success and the data from the API call
	 * 
	 * @param data The data that you want to send to the API.
	 * 
	 * @return A JSON object with a status of success and the data from the API call.
	 */
	protected static function success($data)
	{
		return [
			'status' 	=> 'success',
			'data'		=> json_decode($data->getBody()->getContents())
		];
	}
}
