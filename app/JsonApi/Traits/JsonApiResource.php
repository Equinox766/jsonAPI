<?php

namespace App\JsonApi\Traits;

use App\Http\Resources\CategoryResource;
use App\JsonApi\Document;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait JsonApiResource
{
    abstract public function toJsonApi(): array;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : array
    {
        if ($request->filled('include')) {
            $this->with['included'] = $this->getIncludes();
        }
        return Document::type($this->getResourceType())
        ->id($this->resource->getRouteKey())
        ->attributes($this->filterAttributes($this->toJsonApi()))
        ->relationshipsLinks($this->getRelationshipLinks())
        ->links([
            'self' => route('api.v1.'.$this->getResourceType().'.show', $this->resource)
        ])
        ->get('data');
    }

    public function getIncludes(): array
    {
        return [];
    }

    public function getRelationshipLinks()
    {
        return [];
    }


    public function withResponse ($request, $response)
    {
        $response->header(
            'Location',
            route('api.v1.'.$this->getResourceType().'.show', $this->resource)
        );
    }

    public function filterAttributes(Array $attributes): array
    {
        return array_filter($attributes, function ($value) {
            if (request()->isNotFilled('fields')) {
                return $this;
            }
            $fields = explode(',', request('fields.'.$this->getResourceType()));

            if($value === $this->getRouteKey()) {
                return in_array($this->getRouteKeyName(), $fields);
            }

            return $value;
        });
    }
    public static function collection($resource): AnonymousResourceCollection
    {
        $collection = parent::collection($resource);

        $collection->with['links'] = ['self' => $resource->path()];

        return $collection;

    }
}
