<?php


namespace MakarenkoSergey\CollapseRelationships\Traits;


use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

use function GuzzleHttp\Psr7\str;

trait HasCollapseRelation
{
    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  Collection  $fields
     * @return array
     */
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        $relatedResources = collect($this->relatedFields)->map(function (string $relatedField) use ($request) {
            /** @var Field $field */
            $field = $request->newResource()->availableFields($request)
                ->where('attribute', $relatedField)
                ->first();
            if (!$field) {
                return null;
            }
            $relation = $this->model()->$relatedField();
            if (!$relation) {
                return null;
            }

            return [
                'resourceName' => $this::uriKey(),
                'field' => $field,
                'relationship' => $relatedField,
                'relationshipType' => lcfirst(get_short_name(get_class($relation)))
            ];
        })->filter(function ($value) {
            return !!$value;
        })->values();

        return array_merge($this->serializeWithId($fields ?: $this->indexFields($request)), [
            'title' => static::title(),
            'actions' => $this->availableActions($request),
            'authorizedToView' => $this->authorizedToView($request),
            'authorizedToCreate' => $this->authorizedToCreate($request),
            'authorizedToUpdate' => $this->authorizedToUpdateForSerialization($request),
            'authorizedToDelete' => $this->authorizedToDeleteForSerialization($request),
            'authorizedToRestore' => static::softDeletes() && $this->authorizedToRestore($request),
            'authorizedToForceDelete' => static::softDeletes() && $this->authorizedToForceDelete($request),
            'softDeletes' => static::softDeletes(),
            'softDeleted' => $this->isSoftDeleted(),
            'relatedResources' => $relatedResources,
        ]);
    }
}
