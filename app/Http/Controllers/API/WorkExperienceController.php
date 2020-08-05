<?php

namespace App\Http\Controllers\API;

use App\WorkExperience;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Http\Resources\Work_Experience as WorkExperienceResource;
use App\User;

class WorkExperienceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $workexperience = WorkExperience::all();

        return $this->sendResponse(WorkExperienceResource::collection($workexperience), 'Work experience retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'work_experience' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['user_id'] = $request->user()->id;
        $workexperience = WorkExperience::create($input);

        return $this->sendResponse(new WorkExperienceResource($workexperience), 'Work experience created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $workexperience = User::find($id)->Experiences;

        if (is_null($workexperience)) {
            return $this->sendError('Work experience not found.');
        }

        return $this->sendResponse($workexperience, 'Work experience retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, WorkExperience $work_Experience)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'work_experience' => 'required',
            'start_date' => 'required|date|before:end_date|before:tomorrow',
            'end_date' => 'required|date|after:start_date|before:tomorrow'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $work_Experience->work_experience = $input['work_experience'];
        $work_Experience->start_date = $input['start_date'];
        $work_Experience->end_date = $input['end_date'];
        $work_Experience->save();

        return $this->sendResponse(new WorkExperienceResource($work_Experience), 'Work experience updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(WorkExperience $work_Experience)
    {
        $work_Experience->delete();

        return $this->sendResponse([], 'Work experience deleted successfully.');
    }
}

