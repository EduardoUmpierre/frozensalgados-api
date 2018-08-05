<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    private $orderRepository;
    private $orderProductRepository;
    private $categoryRepository;
    private $userRepository;
    private $customerRepository;
    private $reportRepository;

    /**
     * PdfController constructor.
     * @param OrderRepository $or
     * @param OrderProductRepository $opr
     * @param CategoryRepository $cr
     * @param UserRepository $ur
     * @param CustomerRepository $cus
     * @param ReportRepository $rr
     */
    public function __construct(OrderRepository $or, OrderProductRepository $opr, CategoryRepository $cr,
                                UserRepository $ur, CustomerRepository $cus, ReportRepository $rr)
    {
        $this->orderRepository = $or;
        $this->orderProductRepository = $opr;
        $this->categoryRepository = $cr;
        $this->userRepository = $ur;
        $this->customerRepository = $cus;
        $this->reportRepository = $rr;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function downloadOrderPdf(Request $request, int $id)
    {
        $order = $this->orderRepository->findOnePdfDataById($id);

        $weight = 0;

        foreach ($order['orderProduct'] as $key => $val) {
            $weight += $val->quantity * $val->product->weight;
        }

        $params = [
            'order' => $order->toArray(),
            'weight' => $weight
        ];

        return $this->downloadPdf('order', $params, 'pedido-' . $id . '.pdf', !!$request->input('stream'));
    }

    /**
     * @param Request $request
     * @param string $type
     * @param string|null $from
     * @param string|null $to
     * @return mixed
     */
    public function downloadReportPdf(Request $request, string $type, string $from = null, string $to = null)
    {
        $report = null;
        $category = 'Pedidos';

        switch ($type) {
            case 'products':
                $report = $this->orderProductRepository->findTotal([$from, $to]);
                $category = 'Produtos';
                break;
            case 'categories':
                $report = $this->categoryRepository->findTotal([$from, $to]);
                $category = 'Categorias';
                break;
            case 'sellers':
                $report = $this->userRepository->findTotal([$from, $to]);
                $category = 'Vendedores';
                break;
            case 'customers':
                $report = $this->customerRepository->findReport($request->user()->id, $request->user()->role, [$from, $to]);
                $category = 'Clientes';
                break;
            default:
                $report = $this->reportRepository->findOrderReport([$from, $to]);
                break;
        }

        $filename = 'relatorio-' . strtolower($category) . '.pdf';

        $params = [
            'report' => $report,
            'category' => $category,
            'from' => $from,
            'to' => $to
        ];

        return $this->downloadPdf('report', $params, $filename);
    }

    /**
     * @param string $view
     * @param array $params
     * @param string $filename
     * @param bool $stream
     * @return mixed
     */
    private function downloadPdf(string $view, array $params, string $filename, bool $stream = false)
    {
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView($view, $params);

        if ($stream) {
            return $pdf->stream();
        }

        return $pdf->download($filename);
    }
}
