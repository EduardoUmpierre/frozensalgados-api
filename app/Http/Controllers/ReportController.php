<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $productRepository;
    private $orderProductRepository;
    private $categoryRepository;
    private $userRepository;
    private $customerRepository;

    /**
     * ReportController constructor.
     * @param ProductRepository $pr
     * @param OrderProductRepository $opr
     * @param CategoryRepository $cr
     * @param UserRepository $ur
     * @param CustomerRepository $cur
     */
    public function __construct(ProductRepository $pr, OrderProductRepository $opr, CategoryRepository $cr,
                                UserRepository $ur, CustomerRepository $cur)
    {
        $this->productRepository = $pr;
        $this->orderProductRepository = $opr;
        $this->categoryRepository = $cr;
        $this->userRepository = $ur;
        $this->customerRepository = $cur;
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
        return $this->orderProductRepository->findOneTotalByProductId($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getProductReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->orderProductRepository->findOneTotalByProductId($id, [$from, $to]);
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

    /**
     * @param Request $request
     * @return array
     */
    public function getCustomerReport(Request $request)
    {
        return $this->customerRepository->findReport($request->user()->id, $request->user()->role);
    }

    /**
     * @param Request $request
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCustomerReportBetweenDates(Request $request, string $from, string $to)
    {
        return $this->customerRepository->findReport($request->user()->id, $request->user()->role, [$from, $to]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getCustomerReportById(int $id)
    {
        return $this->customerRepository->findReportById($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getCustomerReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->customerRepository->findReportById($id, [$from, $to]);
    }
}