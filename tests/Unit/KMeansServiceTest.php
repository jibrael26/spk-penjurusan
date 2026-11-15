<?php

namespace Tests\Unit;

use App\Models\AssessmentScore;
use App\Models\Criteria;
use App\Services\KMeansService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class KMeansServiceTest extends TestCase
{
    public function test_it_assigns_a_new_profile_to_a_kmeans_cluster(): void
    {
        $criteria = new Collection();
        foreach (['K01' => 'A', 'K02' => 'B'] as $code => $name) {
            $criterion = new Criteria();
            $criterion->setAttribute('kode_kriteria', $code);
            $criterion->setAttribute('nama_kriteria', $name);
            $criterion->setAttribute('feature_profile', [
                'riasec' => ['r' => $code === 'K01' ? 100 : 0],
                'chc' => ['gf' => $code === 'K01' ? 100 : 0],
            ]);
            $criteria->push($criterion);
        }

        $assessments = new Collection();
        foreach ([90, 10, 85, 15] as $score) {
            $assessment = new AssessmentScore();
            $assessment->setAttribute('riasec_scores', ['r' => $score]);
            $assessment->setAttribute('chc_scores', ['gf' => $score]);
            $assessments->push($assessment);
        }

        $result = app(KMeansService::class)->trainAndAssign(
            $assessments,
            ['r' => 95, 'gf' => 95],
            $criteria
        );

        $this->assertSame(2, $result['k']);
        $this->assertArrayHasKey('sse', $result);
        $this->assertArrayHasKey('silhouette', $result);
        $this->assertSame('k01', $result['kode_jurusan']);

        $projection = app(KMeansService::class)->projectTo2D([
            'r' => 0.9, 'i' => 0, 'a' => 0, 's' => 0, 'e' => 0, 'c' => 0,
            'gf' => 0.9, 'gc' => 0, 'gv' => 0, 'gq' => 0, 'gwm' => 0, 'gs' => 0,
        ]);
        $this->assertSame(15.0, $projection['x']);
        $this->assertSame(15.0, $projection['y']);
    }
}
