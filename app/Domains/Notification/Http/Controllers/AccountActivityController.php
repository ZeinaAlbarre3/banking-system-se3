<?php


namespace App\Domains\Notification\Http\Controllers;

use App\Domains\Notification\Models\Notification;
use App\Http\Controllers\Controller;
use App\Domains\Notification\Services\AccountActivityService;
use App\Domains\Notification\Http\Requests\StoreAccountActivityRequest;
use App\Domains\Notification\Data\AccountActivityCreateData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use App\Domains\Notification\Http\Resources\NotificationResource;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;

class AccountActivityController extends Controller
{
   use AuthorizesRequests;
    public function __construct(
        private readonly AccountActivityService          $service,
        private readonly NotificationRepositoryInterface $notificationRepo
    )
    {
    }

    public function store(StoreAccountActivityRequest $request): JsonResponse
    {
        $data = AccountActivityCreateData::from(
            array_merge(

                $request->validated(),
                ['user_reference' => auth()->id()]
            ));

        $activity = $this->service->create($data);

        return self::Success(data: $activity, msg: 'Activity created and notifications processed');
    }

    public function notifications(): JsonResponse
    {
        $user = auth()->user();
        $paginator = $this->notificationRepo->forUser($user->id, 15);

        return self::Success(data: NotificationResource::collection($paginator));
    }

    /**
     * @throws AuthorizationException
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $this->authorize('view', $notification);

        $this->notificationRepo->markAsRead($notification);

        return self::Success(msg: 'Marked as read');
    }

}
