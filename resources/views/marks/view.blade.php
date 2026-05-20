@extends('layouts.app')
@section('page-title', 'View Marks')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> View Marks</h1>
</div>

<div class="mb-4">
    <h5 class="text-sm font-semibold text-gray-700">Class 1, Section #1</h5>
    <h5 class="text-sm font-semibold text-gray-700">Course: Math</h5>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Full Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 1</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 2</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 1</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 2</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Midterm</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 3</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quiz 4</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignment 1</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignment 2</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Practical</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Final</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-700">1012</td>
                <td class="px-4 py-3 text-gray-600">hamsterclover</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">9</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">9</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">2020-01-24 19:52:28</td>
                <td class="px-4 py-3 text-gray-600">2018-05-21 22:10:21</td>
                <td class="px-4 py-3 text-gray-600">2020-01-24 19:52:28</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">9</td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-700">1013</td>
                <td class="px-4 py-3 text-gray-600">cellofruit</td>
                <td class="px-4 py-3 text-gray-600">4,126</td>
                <td class="px-4 py-3 text-gray-600">9</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">7</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">2020-01-21 11:05:16</td>
                <td class="px-4 py-3 text-gray-600">2019-02-05 17:41:19</td>
                <td class="px-4 py-3 text-gray-600">2020-01-24 19:52:28</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">9</td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-700">1014</td>
                <td class="px-4 py-3 text-gray-600">enchantingsun</td>
                <td class="px-4 py-3 text-gray-600">4,085</td>
                <td class="px-4 py-3 text-gray-600">5</td>
                <td class="px-4 py-3 text-gray-600">4</td>
                <td class="px-4 py-3 text-gray-600">9</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">2020-01-22 18:44:33</td>
                <td class="px-4 py-3 text-gray-600">2019-03-28 12:16:02</td>
                <td class="px-4 py-3 text-gray-600">2020-01-24 19:52:28</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">9</td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-700">1015</td>
                <td class="px-4 py-3 text-gray-600">antdory</td>
                <td class="px-4 py-3 text-gray-600">4,139</td>
                <td class="px-4 py-3 text-gray-600">7</td>
                <td class="px-4 py-3 text-gray-600">5</td>
                <td class="px-4 py-3 text-gray-600">9</td>
                <td class="px-4 py-3 text-gray-600">3</td>
                <td class="px-4 py-3 text-gray-600">2020-01-25 18:12:58</td>
                <td class="px-4 py-3 text-gray-600">2019-06-07 11:44:37</td>
                <td class="px-4 py-3 text-gray-600">2020-01-24 19:52:28</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">4,230</td>
                <td class="px-4 py-3 text-gray-600">9</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
