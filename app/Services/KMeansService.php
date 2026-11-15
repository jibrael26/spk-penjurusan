<?php

namespace App\Services;

use App\Models\AssessmentScore;
use App\Models\Criteria;
use Illuminate\Support\Collection;
use RuntimeException;

class KMeansService
{
    private const FEATURE_KEYS = [
        'r', 'i', 'a', 's', 'e', 'c',
        'gf', 'gc', 'gv', 'gq', 'gwm', 'gs',
    ];

    /**
     * Train K-Means on all available assessment vectors and classify the new vector.
     *
     * @param  array<string, float|int>  $newVector
     * @param  array<string, array{riasec?: array<string, float|int>, chc?: array<string, float|int>}>  $fallbackProfiles
     */
    public function trainAndAssign(
        Collection $assessments,
        array $newVector,
        Collection $criteria,
        array $fallbackProfiles = []
    ): array {
        if ($criteria->isEmpty()) {
            throw new RuntimeException('Tidak ada jurusan aktif untuk membentuk cluster K-Means.');
        }

        $points = $assessments
            ->map(fn (AssessmentScore $assessment) => $this->vectorFromAssessment($assessment))
            ->filter()
            ->values()
            ->all();
        $points[] = $this->normalizeVector($newVector);

        $k = min($criteria->count(), count($points));
        $result = $this->runKMeans($points, $k);
        $mapping = $this->mapCentroidsToCriteria($result['centroids'], $criteria, $assessments, $fallbackProfiles);

        $newClusterIndex = $this->nearestCentroid($points[array_key_last($points)], $result['centroids']);
        $recommendedCriteria = $mapping[$newClusterIndex] ?? $criteria->sortBy('id')->first();

        if (!$recommendedCriteria) {
            throw new RuntimeException('K-Means tidak dapat memetakan cluster ke jurusan.');
        }

        $assignedClusters = [];
        foreach ($result['clusters'] as $index => $cluster) {
            $assignedClusters[$index] = [
                'jurusan' => $mapping[$index]->kode_kriteria ?? null,
                'jumlah_data' => count($cluster),
                'centroid' => $result['centroids'][$index],
            ];
        }

        return [
            'kode_jurusan' => strtolower($recommendedCriteria->kode_kriteria),
            'nama_jurusan' => $recommendedCriteria->nama_kriteria,
            'cluster_index' => $newClusterIndex,
            'k' => $k,
            'iterasi' => $result['iterations'],
            'sse' => round($result['sse'], 4),
            'silhouette' => round($this->silhouette($points, $result['clusters']), 4),
            'jarak_centroid' => round($this->distance($points[array_key_last($points)], $result['centroids'][$newClusterIndex]), 4),
            'clusters' => $assignedClusters,
        ];
    }

    /**
     * @return array<string, float>
     */
    public function vectorFromAssessment(AssessmentScore $assessment): array
    {
        if (!is_array($assessment->riasec_scores) || !is_array($assessment->chc_scores)) {
            return [];
        }

        return $this->normalizeVector(array_merge($assessment->riasec_scores, $assessment->chc_scores));
    }

    /**
     * Project a normalized feature vector to an interpretable 2D view.
     * X is the mean RIASEC score and Y is the mean CHC score.
     *
     * @param  array<string, float|int>  $vector
     * @return array{x: float, y: float}
     */
    public function projectTo2D(array $vector): array
    {
        $normalized = $this->isNormalized($vector)
            ? $vector
            : $this->normalizeVector($vector);
        $riasec = array_slice($normalized, 0, 6);
        $chc = array_slice($normalized, 6, 6);

        return [
            'x' => round(array_sum($riasec) / count($riasec) * 100, 2),
            'y' => round(array_sum($chc) / count($chc) * 100, 2),
        ];
    }

    private function isNormalized(array $vector): bool
    {
        foreach (self::FEATURE_KEYS as $key) {
            if (!array_key_exists($key, $vector)) {
                return false;
            }
            if ((float) ($vector[$key] ?? 0) > 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, float|int>  $vector
     * @return array<string, float>
     */
    private function normalizeVector(array $vector): array
    {
        $normalized = [];
        foreach (self::FEATURE_KEYS as $key) {
            $normalized[$key] = max(0, min(100, (float) ($vector[$key] ?? 0))) / 100;
        }

        return $normalized;
    }

    private function runKMeans(array $points, int $k): array
    {
        $centroids = [$points[0]];
        while (count($centroids) < $k) {
            $farthest = null;
            $farthestDistance = -1;
            foreach ($points as $point) {
                $distance = min(array_map(fn (array $centroid) => $this->distance($point, $centroid), $centroids));
                if ($distance > $farthestDistance) {
                    $farthestDistance = $distance;
                    $farthest = $point;
                }
            }
            $centroids[] = $farthest;
        }

        $clusters = [];
        $iterations = 0;
        for (; $iterations < 100; $iterations++) {
            $newClusters = array_fill(0, $k, []);
            foreach ($points as $pointIndex => $point) {
                $newClusters[$this->nearestCentroid($point, $centroids)][] = $pointIndex;
            }

            $newCentroids = [];
            foreach ($newClusters as $index => $cluster) {
                $newCentroids[$index] = $cluster
                    ? $this->mean(array_map(fn (int $pointIndex) => $points[$pointIndex], $cluster))
                    : $centroids[$index];
            }

            if ($this->centroidsEqual($centroids, $newCentroids)) {
                $clusters = $newClusters;
                $centroids = $newCentroids;
                break;
            }

            $clusters = $newClusters;
            $centroids = $newCentroids;
        }

        $sse = 0;
        foreach ($clusters as $index => $cluster) {
            foreach ($cluster as $pointIndex) {
                $sse += $this->distance($points[$pointIndex], $centroids[$index]) ** 2;
            }
        }

        return compact('centroids', 'clusters', 'iterations', 'sse');
    }

    private function nearestCentroid(array $point, array $centroids): int
    {
        $nearest = 0;
        $minimum = INF;
        foreach ($centroids as $index => $centroid) {
            $distance = $this->distance($point, $centroid);
            if ($distance < $minimum) {
                $minimum = $distance;
                $nearest = $index;
            }
        }

        return $nearest;
    }

    private function distance(array $first, array $second): float
    {
        $sum = 0;
        foreach (self::FEATURE_KEYS as $key) {
            $sum += (($first[$key] ?? 0) - ($second[$key] ?? 0)) ** 2;
        }

        return sqrt($sum);
    }

    private function mean(array $points): array
    {
        $mean = array_fill_keys(self::FEATURE_KEYS, 0.0);
        foreach ($points as $point) {
            foreach (self::FEATURE_KEYS as $key) {
                $mean[$key] += $point[$key] ?? 0;
            }
        }

        foreach ($mean as $key => $value) {
            $mean[$key] = $value / count($points);
        }

        return $mean;
    }

    private function centroidsEqual(array $first, array $second): bool
    {
        foreach ($first as $index => $centroid) {
            if ($this->distance($centroid, $second[$index]) > 0.000001) {
                return false;
            }
        }

        return true;
    }

    private function mapCentroidsToCriteria(
        array $centroids,
        Collection $criteria,
        Collection $assessments,
        array $fallbackProfiles
    ): array {
        $available = $criteria->sortBy('id')->values()->all();
        $mapping = [];
        foreach ($centroids as $clusterIndex => $centroid) {
            $bestIndex = null;
            $bestDistance = INF;
            foreach ($available as $index => $criterion) {
                $profile = $this->profileFor($criterion, $assessments, $fallbackProfiles);
                if ($profile === null) {
                    continue;
                }
                $distance = $this->distance($centroid, $profile);
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestIndex = $index;
                }
            }

            $chosen = $bestIndex !== null ? $available[$bestIndex] : ($available[0] ?? null);
            if ($chosen) {
                $mapping[$clusterIndex] = $chosen;
                array_splice($available, $bestIndex ?? 0, 1);
            }
        }

        return $mapping;
    }

    private function profileFor(Criteria $criterion, Collection $assessments, array $fallbackProfiles): ?array
    {
        if (is_array($criterion->feature_profile)) {
            return $this->normalizeVector(array_merge(
                $criterion->feature_profile['riasec'] ?? [],
                $criterion->feature_profile['chc'] ?? []
            ));
        }

        $code = strtolower($criterion->kode_kriteria);
        if (isset($fallbackProfiles[$code])) {
            return $this->normalizeVector(array_merge(
                $fallbackProfiles[$code]['riasec'] ?? [],
                $fallbackProfiles[$code]['chc'] ?? []
            ));
        }

        $historical = $assessments->filter(function (AssessmentScore $assessment) use ($code, $criterion) {
            $label = strtolower((string) $assessment->recommended_cluster);
            return $label === $code || $label === strtolower($criterion->nama_kriteria);
        });
        if ($historical->isNotEmpty()) {
            $vectors = $historical->map(fn (AssessmentScore $assessment) => $this->vectorFromAssessment($assessment))->filter()->all();
            return $vectors ? $this->mean($vectors) : null;
        }

        return null;
    }

    private function silhouette(array $points, array $clusters): float
    {
        $values = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $pointIndex) {
                if (count($cluster) < 2) {
                    continue;
                }
                $within = array_sum(array_map(
                    fn (int $other) => $this->distance($points[$pointIndex], $points[$other]),
                    array_filter($cluster, fn (int $other) => $other !== $pointIndex)
                )) / (count($cluster) - 1);
                $between = INF;
                foreach ($clusters as $otherIndex => $otherCluster) {
                    if ($otherIndex === $clusterIndex || !$otherCluster) {
                        continue;
                    }
                    $average = array_sum(array_map(
                        fn (int $other) => $this->distance($points[$pointIndex], $points[$other]),
                        $otherCluster
                    )) / count($otherCluster);
                    $between = min($between, $average);
                }
                $values[] = ($between - $within) / max($within, $between, 0.000001);
            }
        }

        return $values ? array_sum($values) / count($values) : 0.0;
    }
}
