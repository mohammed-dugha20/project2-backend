<?php

namespace App\Providers;

use App\Repositories\Admin\AdminAuthRepository;
use App\Repositories\Admin\AdminDisputeRepository;
use App\Repositories\Admin\AdminFinishingCompanyRepository;
use App\Repositories\Admin\AdminRealEstateOfficeRepository;
use App\Repositories\Admin\Interfaces\AdminAuthRepositoryInterface;
use App\Repositories\Admin\Interfaces\AdminDisputeRepositoryInterface;
use App\Repositories\Admin\Interfaces\AdminFinishingCompanyRepositoryInterface;
use App\Repositories\Admin\Interfaces\AdminRealEstateOfficeRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\EloquentPropertyRepository;
use App\Repositories\EloquentRealEstateOfficeRepository;
use App\Repositories\FinishingCompanyManagementRepository;
use App\Repositories\Interfaces\FinishingCompanyManagementRepositoryInterface;
use App\Repositories\Interfaces\RealEstateOfficeRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RealEstateOfficeRepositoryInterface::class,
            EloquentRealEstateOfficeRepository::class
        );

        $this->app->bind(
            FinishingCompanyManagementRepositoryInterface::class,
            FinishingCompanyManagementRepository::class
        );

        $this->app->bind(
            AdminFinishingCompanyRepositoryInterface::class,
            AdminFinishingCompanyRepository::class
        );

        $this->app->bind(
            AdminDisputeRepositoryInterface::class,
            AdminDisputeRepository::class
        );

        $this->app->bind(
            AdminAuthRepositoryInterface::class,
            AdminAuthRepository::class
        );

        $this->app->bind(
            AdminRealEstateOfficeRepositoryInterface::class,
            AdminRealEstateOfficeRepository::class
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

        $this->app->bind(
            PropertyRepositoryInterface::class,
            EloquentPropertyRepository::class
        );

        $this->app->bind(
            \App\Repositories\Admin\Interfaces\AdminRepositoryInterface::class,
            \App\Repositories\Admin\AdminRepository::class
        );
    }
} 