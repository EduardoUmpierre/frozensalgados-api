<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;

class ReportController extends Controller
{
    private $productRepository;
    private $orderProductRepository;
    private $categoryRepository;
    private $userRepository;

    /**
     * ReportController constructor.
     * @param ProductRepository $pr
     * @param OrderProductRepository $opr
     * @param CategoryRepository $cr
     * @param UserRepository $ur
     */
    public function __construct(ProductRepository $pr, OrderProductRepository $opr, CategoryRepository $cr,
                                UserRepository $ur)
    {
        $this->productRepository = $pr;
        $this->orderProductRepository = $opr;
        $this->categoryRepository = $cr;
        $this->userRepository = $ur;
    }

    /**
     * @return array
     */
    public function getProductReport()
    {
        return $this->orderProductRepository->findTotal();
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getProductReportBetweenDates(string $from, string $to)
    {
        return $this->orderProductRepository->findTotal([$from, $to]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getProductReportById(int $id)
    {
        return $this->orderProductRepository->findTotalByProductId($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getProductReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->orderProductRepository->findTotalByProductId($id, [$from, $to]);
    }

    /**
     * @return array
     */
    public function getCategoryReport()
    {
        return $this->categoryRepository->findTotal();
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCategoryReportBetweenDates(string $from, string $to)
    {
        return $this->categoryRepository->findTotal([$from, $to]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getCategoryReportById(int $id)
    {
        return $this->categoryRepository->findTotalById($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCategoryReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->categoryRepository->findTotalById($id, [$from, $to]);
    }

    /**
     * @return array
     */
    public function getSellerReport()
    {
        return $this->userRepository->findTotal();
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getSellerReportBetweenDates(string $from, string $to)
    {
        return $this->userRepository->findTotal([$from, $to]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getSellerReportById(int $id)
    {
        return $this->userRepository->findTotalById($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getSellerReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->userRepository->findTotalById($id, [$from, $to]);
    }
}