<?php

namespace App\Http\Controllers;

use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;

class PdfController extends Controller
{
    private $orderRepository;
    private $orderProductRepository;

    /**
     * PdfController constructor.
     * @param OrderRepository $or
     */
    public function __construct(OrderRepository $or, OrderProductRepository $opr)
    {
        $this->orderRepository = $or;
        $this->orderProductRepository = $opr;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function downloadOrderPdf(int $id)
    {
        $order = $this->orderRepository->findOnePdfDataById($id);

        $weight = 0;

        foreach ($order['orderProduct'] as $key => $val) {
            $weight += $val->quantity * $val->product->weight;
        }

        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('order', ['order' => $order->toArray(), 'weight' => $weight]);

        return $pdf->stream();
    }
}
