<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view documents list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        // Admins can view all documents
        if ($user->isAdmin()) {
            return true;
        }

        // Published documents can be viewed by everyone
        if ($document->isPublished()) {
            return true;
        }

        // Authors can view their own drafts
        return $document->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canEdit(); // Admins and Editors can create
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        // Admins can update any document
        if ($user->isAdmin()) {
            return true;
        }

        // Editors can update their own documents
        if ($user->isEditor() && $document->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        // Admins can delete any document
        if ($user->isAdmin()) {
            return true;
        }

        // Editors can delete their own documents
        if ($user->isEditor() && $document->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->isAdmin(); // Only admins can restore
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->isAdmin(); // Only admins can force delete
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEditor() && $document->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEditor() && $document->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
