<?php

namespace App\Http\Controllers;

use App\DTO\Contact\CreateContactDTO;
use App\DTO\Contact\UpdateContactDTO;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\Contact\CreateContactService;
use App\Services\Contact\DeleteContactService;
use App\Services\Contact\UpdateContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $contacts = Contact::query()
            ->where('userId', $request->user()->id)
            ->when(
                $request->query('search'),
                fn ($query, $search) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                })
            )
            ->orderBy('name')
            ->paginate($request->integer('perPage', 20));

        return ContactResource::collection($contacts);
    }

    public function store(
        StoreContactRequest $request,
        CreateContactService $service
    ): JsonResponse {
        $contact = $service->handle(
            new CreateContactDTO(
                userId: $request->user()->id,
                name: $request->validated('name'),
                email: $request->validated('email'),
                phone: $request->validated('phone'),
                company: $request->validated('company'),
                notes: $request->validated('notes'),
            )
        );

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Contact $contact): JsonResponse
    {
        abort_unless($contact->userId === request()->user()?->id, 403);

        return (new ContactResource($contact))->response();
    }

    public function update(
        UpdateContactRequest $request,
        Contact $contact,
        UpdateContactService $service
    ): JsonResponse {
        abort_unless($contact->userId === $request->user()?->id, 403);

        $contact = $service->handle(
            $contact,
            new UpdateContactDTO(
                attributes: $request->safe()->only([
                    'name',
                    'email',
                    'phone',
                    'company',
                    'notes',
                ])
            )
        );

        return (new ContactResource($contact))->response();
    }

    public function destroy(
        Contact $contact,
        DeleteContactService $service
    ): Response {
        abort_unless($contact->userId === request()->user()?->id, 403);

        $service->handle($contact);

        return response()->noContent();
    }
}