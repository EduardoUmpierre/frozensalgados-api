<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\ReportRepository;
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
    private $reportRepository;

    /**
     * ReportController constructor.
     * @param ProductRepository $pr
     * @param OrderProductRepository $opr
     * @param CategoryRepository $cr
     * @param UserRepository $ur
     * @param CustomerRepository $cur
     * @param ReportRepository $rr
     */
    public function __construct(ProductRepository $pr, OrderProductRepository $opr, CategoryRepository $cr,
                                UserRepository $ur, CustomerRepository $cur, ReportRepository $rr)
    {
        $this->productRepository = $pr;
        $this->orderProductRepository = $opr;
        $this->categoryRepository = $cr;
        $this->userRepository = $ur;
        $this->customerRepository = $cur;
        $this->reportRepository = $rr;
    }

    /**
     * @return array
     */
    public function getProductReport()
    {
        return $this->orderProductRepository->findTotal();
    }

    /**
     * @param Request $request
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getProductReportBetweenDates(Request $request, string $from, string $to)
    {
        $request['from'] = $from;
        $request['to'] = $to;

        $this->validate($request, [
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

        return $this->orderProductRepository->findTotal([$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return OrderProductRepository|\Illuminate\Database\Eloquent\Model
     */
    public function getProductReportById(Request $request, string $id)
    {
        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        return $this->orderProductRepository->findOneTotalByProductId($id);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string $from
     * @param string $to
     * @return OrderProductRepository|\Illuminate\Database\Eloquent\Model
     */
    public function getProductReportBetweenDatesById(Request $request, string $id, string $from, string $to)
    {
        $request['id'] = $id;
        $request['from'] = $from;
        $request['to'] = $to;

        $this->validate($request, [
            'id' => 'required|numeric',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

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
     * @param Request $request
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCategoryReportBetweenDates(Request $request, string $from, string $to)
    {
        $request['from'] = $from;
        $request['to'] = $to;

        $this->validate($request, [
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

        return $this->categoryRepository->findTotal([$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return array
     */
    public function getCategoryReportById(Request $request, string $id)
    {
        $request['id'] = $id;

        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        return $this->categoryRepository->findTotalById($id);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCategoryReportBetweenDatesById(Request $request, string $id, string $from, string $to)
    {
        $request['id'] = $id;
        $request['from'] = $from;
        $request['to'] = $to;

        $this->validate($request, [
            'id' => 'required|numeric',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

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

    /**
     * @param string $from
     * @param string $to
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPeriodReport(string $from, string $to)
    {
        return $this->reportRepository->findReport([$from, $to]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderReport()
    {
        return $this->reportRepository->findOrderReport();
    }

    /**
     * @param string $from
     * @param string $to
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderReportBetweenDates(string $from, string $to)
    {
        return $this->reportRepository->findOrderReport([$from, $to]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderReportById(int $id)
    {
        return $this->reportRepository->findOrderReportById($id);
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderReportBetweenDatesById(int $id, string $from, string $to)
    {
        return $this->reportRepository->findOrderReportById($id, [$from, $to]);
    }
}
