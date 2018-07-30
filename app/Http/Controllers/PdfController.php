<?php

namespace App\Http\Controllers;

use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    private $orderRepository;
    private $orderProductRepository;

    /**
     * PdfController constructor.
     * @param OrderRepository $or
     * @param OrderProductRepository $opr
     */
    public function __construct(OrderRepository $or, OrderProductRepository $opr)
    {
        $this->orderRepository = $or;
        $this->orderProductRepository = $opr;
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

        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('order', ['order' => $order->toArray(), 'weight' => $weight]);

        $stream = $request->input('stream');

        if ($stream) {
            return $pdf->stream();
        }

        return $pdf->download('pedido-' . $id . '.pdf');
    }
}
