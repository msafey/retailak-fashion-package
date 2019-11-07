<?php
/**
 * Created by PhpStorm.
 * User: ahly82
 * Date: 6/17/2017
 * Time: 1:58 PM
 */


namespace App\Http\Controllers;
use Illuminate\Http\Response as IlluminateResponse;
use Response;

use Illuminate\Foundation\Console\IlluminateCaster;
//use Response;

class ApiController extends Controller
{
    protected $statusCode = 200;

    Public function getStatusCode()
    {
        return $this->statusCode;
    }


    Public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    Public function respondNotFound($status = 'Erorr', $message = 'Not Found !')//Not Found
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithErorr($status, $message);
    }

    Public function respondMissingHeaders($status = 'Erorr', $message = 'Missing Headers !')//Not Found
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithErorr($status, $message);
    }


    Public function respondInternalErorr($status = 'Erorr', $message = 'Internal Server Erorr !')//Server Erorr
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithErorr($status, $message);
    }

    Public function respondValidationErorr($status = 'Erorr', $message = 'Bad Request')//Validation Erorr
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST)->respondWithErorr($status, $message);
    }

    Public function respondAuthErorr($status = 'Erorr', $message = 'Unauthorized')//Validation Erorr
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)->respondWithErorr($status, $message);
    }


    Public function respondCeated( $status = 'Success', $message = 'Item Added Successfully')//Created Successfull
    {
            return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respondWithErorr($status, $message);
    }

    Public function respondDeleted( $status = 'Success', $message = 'Item Deleted Successfully')//Created Successfull
    {
            return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respondWithErorr($status, $message);
    }

    Public function respondExist($status = 'Success', $message = 'Item Already Exist')//Created Successfull
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST)->respondWithErorr($status, $message);
    }

    Public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    Public function respondWithErorr($status, $message)
    {
        return $this->respond([

            'message' => $message,
//            'status_code' => $this->getStatusCode(),
            'status' => $status,

        ]);
    }

}