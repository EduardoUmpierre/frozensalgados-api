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
     * @param Request $request
     * @param string|null $from
     * @param string|null $to
     * @return array
     */
    public function getProductReport(Request $request, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to), $this->getValidationParams($from, $to));

        return $this->orderProductRepository->findTotal([$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string|null $from
     * @param string|null $to
     * @return OrderProductRepository|\Illuminate\Database\Eloquent\Model
     */
    public function getProductReportById(Request $request, string $id, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to, $id), $this->getValidationParams($from, $to, $id));

        return $this->orderProductRepository->findOneTotalByProductId($id, [$from, $to]);
    }

    /**
     * @param Request $request
     * @param string|null $from
     * @param string|null $to
     * @return array
     */
    public function getCategoryReport(Request $request, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to), $this->getValidationParams($from, $to));

        return $this->categoryRepository->findTotal([$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getCategoryReportById(Request $request, string $id, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to, $id), $this->getValidationParams($from, $to, $id));

        return $this->categoryRepository->findTotalById($id, [$from, $to]);
    }

    /**
     * @param Request $request
     * @param string|null $from
     * @param string|null $to
     * @return array
     */
    public function getSellerReport(Request $request, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to), $this->getValidationParams($from, $to));

        return $this->userRepository->findTotal([$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string|null $from
     * @param string|null $to
     * @return UserRepository|\Illuminate\Database\Eloquent\Model
     */
    public function getSellerReportById(Request $request, string $id, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to, $id), $this->getValidationParams($from, $to, $id));

        return $this->userRepository->findTotalById($id, [$from, $to]);
    }

    /**
     * @param Request $request
     * @param string|null $from
     * @param string|null $to
     * @return array
     */
    public function getCustomerReport(Request $request, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to), $this->getValidationParams($from, $to));

        return $this->customerRepository->findReport($request->user()->id, $request->user()->role, [$from, $to]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string|null $from
     * @param string|null $to
     * @return CustomerRepository|\Illuminate\Database\Eloquent\Model
     */
    public function getCustomerReportById(Request $request, string $id, string $from = null, string $to = null)
    {
        $this->validate($this->getValidationRequest($request, $from, $to, $id), $this->getValidationParams($from, $to, $id));

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
     * @param string|null $from
     * @param string|null $to
     * @return array
     */
    public function getOrderReport(string $from = null, string $to = null)
    {
        return $this->reportRepository->findOrderReport([$from, $to]);
    }

    /**
     * @param int $id
     * @param string|null $from
     * @param string|null $to
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderReportById(int $id, string $from = null, string $to = null)
    {
        return $this->reportRepository->findOrderReportById($id, [$from, $to]);
    }

    /**
     * @param $from
     * @param $to
     * @param string|null $id
     * @return array
     */
    private function getValidationParams($from, $to, string $id = null)
    {
        $params = [];

        if ($id) {
            $params['id'] = 'required|numeric';
        }

        if ($from && $to) {
            $params['from'] = 'required|date';
            $params['to'] = 'required|date';
        }

        return $params;
    }

    /**
     * @param Request $request
     * @param $from
     * @param $to
     * @param string|null $id
     * @return Request
     */
    private function getValidationRequest(Request $request, $from, $to, string $id = null)
    {
        if ($id) {
            $request['id'] = $id;
        }

        if ($from && $to) {
            $request['from'] = $from;
            $request['to'] = $to;
        }

        return $request;
    }
}
