<?php

namespace App\Domains\Account\Http\Controllers;

use App\Domains\Account\Data\AccountCreateData;
use App\Domains\Account\Data\AccountStateChangeData;
use App\Domains\Account\Data\AccountUpdateData;
use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Http\Requests\ChangeAccountStateRequest;
use App\Domains\Account\Http\Requests\InterestRequest;
use App\Domains\Account\Http\Requests\StoreAccountRequest;
use App\Domains\Account\Http\Requests\UpdateAccountRequest;
use App\Domains\Account\Http\Resources\AccountInterestResource;
use App\Domains\Account\Http\Resources\AccountPortfolioBalanceResource;
use App\Domains\Account\Http\Resources\AccountResource;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Services\AccountService;
use App\Domains\Account\Services\InterestService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    public function __construct(
        private readonly AccountService $service,
        private readonly InterestService $interestService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $accounts = Account::all();

        return self::Success(data: AccountResource::collection($accounts));
    }

    public function myAccounts(Request $request): JsonResponse
    {
        $accounts = $this->service->getMyAccounts();

        return self::Success(data: AccountResource::collection($accounts));
    }

    public function store(StoreAccountRequest $request): JsonResponse
    {
        $data = AccountCreateData::from($request->validated());

        $account = $this->service->create($data);

        return self::Success(new AccountResource($account),msg:'Account has been created successfully');
    }

    public function show(Request $request, Account $account): JsonResponse
    {
        Gate::authorize('view', $account);

        return self::Success(data: new AccountResource($account));

    }

    public function update(UpdateAccountRequest $request, Account $account): JsonResponse
    {
        //policy (memento)

        $data = AccountUpdateData::from($request->validated());

        $account = $this->service->update($account, $data);

        return self::Success(new AccountResource($account),msg:'Account has been updated successfully');
    }

    public function changeState(ChangeAccountStateRequest $request, Account $account): JsonResponse
    {
        //policy (memento)

        $data = AccountStateChangeData::from($request->validated());

        $account = $this->service->changeState($account, $data);

        return self::Success(new AccountResource($account),msg:'Account state has been updated successfully');
    }

    public function portfolioBalance(Request $request): JsonResponse
    {
        //policy (memento)

        $balance = $this->service->getUserPortfolioBalance(Auth::user());

        return self::Success(new AccountPortfolioBalanceResource($balance));
    }

    public function interest(InterestRequest $request, Account $account): JsonResponse
    {
        $ctx = InterestData::fromRequest($request->validated());

        $result = $this->interestService->calculate($account, $ctx);

        return self::Success(new AccountInterestResource($result));
    }
}

