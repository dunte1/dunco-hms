<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ModalityWorklistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModalityWorklistController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = ModalityWorklistItem::with(['patient', 'radiologyRequest']);

        if ($request->filled('modality')) {
            $query->where('modality', $request->input('modality'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('scheduled_time')->paginate($perPage);

        return $this->paginated($paginator);
    }
}
