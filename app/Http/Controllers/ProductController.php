<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="index",
     *      tags={"Product"},
     *      summary="Get all products",
     *      description="Get all products",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="Get All products"),
     *              @OA\Property(property="data", type="array",
     *              @OA\Items(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Product 1"),
     *              @OA\Property(property="price", type="integer", example=10000),
     *              @OA\Property(property="description", type="string", example="Description product 1"),
     *              @OA\Property(property="image", type="string", example="image.jpg"),
     *              )
     *            )
     *        )
     *     ),
     * )
     */
    public function index()
    {
        // $products = Product::all();

        // paginate HATEOAS
        $products = Product::paginate(10);

        // $data = [
        //     'status' => 200,
        //     'message' => 'Data produk berhasil diambil',
        //     'data' => $products
        // ];

        return response()->json($products, Response::HTTP_OK);
    }

    // detail product
    public function show($id)
    {
        $product = Product::find($id);

        // jika data tidak ditemukan
        if (is_null($product)) {
            $data = [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data produk tidak ditemukan',
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        } else {
            $data = [
                'status' => Response::HTTP_OK,
                'message' => 'Data produk berhasil ditemukan',
                'data' => $product
            ];
            return response()->json($data, Response::HTTP_OK);
        }
    }

    // create product
    public function store(Request $request)
    {
        // validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required|string|',
            'image' => 'image|required|max:2048|mimes:png,jpg,jpeg'
        ]);

        $input = $request->all();

        // logika upload gambar
        if ($image = $request->file('image')) {
            $target = 'assets/images/';
            $product_img = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($target, $product_img);
            $input['image'] = $product_img;
        }

        // masukan data ke database
        Product::create($input);

        $data = [
            'status' => Response::HTTP_CREATED,
            'message' => 'Data produk berhasil ditambahkan',
            'data' => $input
        ];
        return response()->json($data, Response::HTTP_CREATED);
    }

    // update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product) {
            // validasi request
            $request->validate([
                'name' => 'string|max:255',
                'price' => 'integer',
                'description' => 'string|',
                // 'image' => 'image|max:2048|mimes:png,jpg,jpeg'
            ]);

            $input = $request->all();

            // logika upload gambar
            if ($image = $request->file('image')) {
                $target = 'assets/images/';
                // jika ada image
                unlink($target . $product->image);
                $product_img = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($target, $product_img);
                $input['image'] = $product_img;
            } else {
                // jika tidak ada image
                $input['image'] = $product->image;
            }

            // update data ke database
            $product->update($input);

            $data = [
                'status' => Response::HTTP_OK,
                'message' => 'Data produk berhasil diupdate',
                'data' => $product
            ];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data = [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data produk tidak ditemukan',
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }

    // delete product
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $target = 'assets/images/';
            unlink($target . $product->image);
            $product->delete();

            $data = [
                'status' => Response::HTTP_OK,
                'message' => 'Data produk berhasil dihapus',
            ];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data = [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data produk tidak ditemukan',
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }
}
